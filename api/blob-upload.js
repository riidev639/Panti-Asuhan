import { handleUpload } from '@vercel/blob/client';

const PHOTO_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
const VIDEO_TYPES = ['video/mp4', 'video/quicktime', 'video/webm', 'video/x-matroska'];

function firstHeader(value) {
    return Array.isArray(value) ? value[0] : value;
}

async function memberIsAuthenticated(request) {
    const forwardedHost = firstHeader(request.headers['x-forwarded-host']);
    const requestHost = firstHeader(request.headers.host);
    const host = forwardedHost || requestHost || '';

    if (!/^[a-z0-9.-]+(?::\d+)?$/i.test(host)) {
        return false;
    }

    const protocol = firstHeader(request.headers['x-forwarded-proto']) || 'https';
    const response = await fetch(`${protocol}://${host}/_internal/blob-authorize`, {
        headers: {
            accept: 'application/json',
            cookie: firstHeader(request.headers.cookie) || '',
        },
        redirect: 'manual',
    });

    return response.ok;
}

export default async function handler(request, response) {
    if (request.method !== 'POST') {
        response.setHeader('Allow', 'POST');
        return response.status(405).json({ message: 'Method not allowed.' });
    }

    try {
        const body = typeof request.body === 'string'
            ? JSON.parse(request.body)
            : request.body;

        if (body?.type === 'blob.generate-client-token' && !await memberIsAuthenticated(request)) {
            return response.status(401).json({ message: 'Silakan login kembali sebelum mengupload file.' });
        }

        const result = await handleUpload({
            request,
            body,
            onBeforeGenerateToken: async (pathname, clientPayload) => {
                let payload;

                try {
                    payload = JSON.parse(clientPayload || '{}');
                } catch {
                    throw new Error('Payload upload tidak valid.');
                }

                const kind = payload.kind;
                const settings = kind === 'photo'
                    ? { directory: 'photos/', types: PHOTO_TYPES, max: 10 * 1024 * 1024 }
                    : kind === 'video'
                        ? { directory: 'videos/', types: VIDEO_TYPES, max: 30 * 1024 * 1024 }
                        : null;

                if (!settings || !pathname.startsWith(settings.directory) || pathname.includes('..')) {
                    throw new Error('Lokasi upload tidak valid.');
                }

                return {
                    allowedContentTypes: settings.types,
                    maximumSizeInBytes: settings.max,
                    addRandomSuffix: true,
                    cacheControlMaxAge: 31536000,
                    tokenPayload: JSON.stringify({ kind }),
                };
            },
        });

        return response.status(200).json(result);
    } catch (error) {
        console.error('Blob upload token error:', error);

        return response.status(400).json({
            message: 'Upload tidak dapat dimulai. Silakan muat ulang halaman dan coba lagi.',
        });
    }
}
