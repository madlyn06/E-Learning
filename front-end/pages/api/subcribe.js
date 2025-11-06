import { API_URL, API_KEY } from '@/utils/config';
import formidable from 'formidable';

export const config = {
	api: {
		bodyParser: false,
	},
};

export default async function handler(req, res) {
	if (req.method === 'POST') {
		const form = formidable({ multiples: true });

		form.parse(req, async (err, fields) => {
			if (err) {
				res.status(500).json({ error: 'Error parsing form data' });
				return;
			}
			const { email } = fields;
			await fetch(`${API_URL}/subscribe`, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'x-api-key': `${API_KEY}`,
				},
				body: JSON.stringify({ email: email[0].trim() }),
			});

			return res.status(200).json({ success: true, email: email[0].trim() });

		});
	} else {
		res.status(405).json({ error: 'Method not allowed' });
	}
}
