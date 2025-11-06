export default async function handler(req, res) {
    if (req.method === 'POST') {

        if (req.body.secret !== process.env.FETCHING_SECRET_TOKEN) {
            return res.status(401).json({ message: 'Invalid token' })
        }
        const { slug, type } = req.body;
        try {
            if (type == 'story') {
                await res.revalidate(`/stories`);
                await res.revalidate(`/stories/${slug}`);
                return res.status(200).json({ message: 'Revalidation story triggered' });
            }
            await res.revalidate(`/${slug}`);
            return res.status(200).json({ message: 'Revalidation triggered' });
        } catch (error) {
            console.log(error.message);
            return res.status(500).json({ message: 'Error triggering revalidation' });
        }
    } else {
        res.status(405).end();
    }
}
