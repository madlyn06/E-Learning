import { useState, useCallback } from 'react'

export function useLoading(initial = false) {
  const [loading, setLoading] = useState(initial)

  const start = useCallback(() => setLoading(true), [])
  const stop = useCallback(() => setLoading(false), [])

  const withLoading = useCallback(async (fn) => {
    setLoading(true)
    try {
      return await fn()
    } finally {
      setLoading(false)
    }
  }, [])

  return { loading, start, stop, withLoading }
}
