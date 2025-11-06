import apiFetch from './request'

export const fetcher = (url) => {
  return apiFetch.get(url)
}
