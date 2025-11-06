import apiFetch from "@/utils/request";

export default async function handler(req, res) {
    const { robots_configuration } = await apiFetch(`configs/${['robots_configuration']}`)

    res.setHeader('Content-Type', 'text/plain');
    res.status(200).send(robots_configuration);
}
