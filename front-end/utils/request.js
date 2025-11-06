import { getToken } from './auth'
import { API_KEY, API_URL } from './config'

function toQuery(params = {}) {
  const q = new URLSearchParams()
  Object.entries(params).forEach(([k, v]) => {
    if (v === undefined || v === null) return
    if (Array.isArray(v)) v.forEach((item) => q.append(k, item))
    else q.append(k, String(v))
  })
  const s = q.toString()
  return s ? `?${s}` : ''
}

async function apiFetch(
  path,
  {
    method = 'GET',
    params,
    body,
    headers = {},
    apiKey = API_KEY,
    timeoutMs = 30000,
    cache = 'no-store',
    next = undefined,
    signal: externalSignal
  } = {}
) {
  const controller = new AbortController()
  const timeout = setTimeout(() => controller.abort(new Error('Request timeout')), timeoutMs)
  const signal = externalSignal ?? controller.signal

  try {
    const url = `${API_URL?.replace(/\/$/, '')}/${path.replace(/^\//, '')}${toQuery(params)}`

    const isForm = typeof FormData !== 'undefined' && body instanceof FormData
    const isJSON = body && !isForm
    const token = typeof window !== 'undefined' ? getToken() : null
    const finalHeaders = {
      ...(apiKey ? { 'X-API-KEY': apiKey } : {}),
      ...(!headers?.Authorization && token ? { Authorization: `Bearer ${token}` } : {}),
      ...(isJSON ? { 'Content-Type': 'application/json' } : {}),
      ...headers
    }

    const res = await fetch(url, {
      method,
      headers: finalHeaders,
      body: isJSON ? JSON.stringify(body) : isForm ? body : undefined,
      cache,
      next,
      signal
    })

    const contentType = res.headers.get('content-type') || ''
    const isJsonResp = contentType.includes('application/json')

    if (!res.ok) {
      const errPayload = isJsonResp ? await res.json().catch(() => ({})) : await res.text().catch(() => '')
      throw {
        status: res.status,
        statusText: res.statusText,
        payload: errPayload
      }
    }

    return isJsonResp ? res.json() : res.text()
  } finally {
    clearTimeout(timeout)
  }
}

// Convenience methods
apiFetch.get = (path, options = {}) => apiFetch(path, { ...options, method: 'GET' })
apiFetch.post = (path, body, options = {}) => apiFetch(path, { ...options, method: 'POST', body })
apiFetch.put = (path, body, options = {}) => apiFetch(path, { ...options, method: 'PUT', body })
apiFetch.patch = (path, body, options = {}) => apiFetch(path, { ...options, method: 'PATCH', body })
apiFetch.delete = (path, options = {}) => apiFetch(path, { ...options, method: 'DELETE' })

export default apiFetch
