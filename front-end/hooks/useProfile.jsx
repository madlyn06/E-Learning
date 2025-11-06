import { LS_KEYS } from '@/utils/const'
import apiFetch from '@/utils/request'
import { useState, useEffect, useCallback } from 'react'

export function useProfile() {
  const [profile, setProfile] = useState(() => {
    if (typeof window !== 'undefined') {
      const cached = localStorage.getItem(LS_KEYS.PROFILE)
      try {
        return cached ? JSON.parse(cached) : null
      } catch {
        localStorage.removeItem(LS_KEYS.PROFILE)
        return null
      }
    }
    return null
  })

  const [loading, setLoading] = useState(false)
  const [error, setError] = useState(null)

  const fetchProfile = useCallback(async () => {
    setLoading(true)
    setError(null)
    try {
      const data = await apiFetch.get('v1/elearning/users/profile')
      setProfile(data.user)
      if (typeof window !== 'undefined') {
        localStorage.setItem(LS_KEYS.PROFILE, JSON.stringify(data.user))
      }
      return data.user
    } catch (err) {
      setError(err)
      throw err
    } finally {
      setLoading(false)
    }
  }, [])

  useEffect(() => {
    if (!profile) {
      fetchProfile().catch(() => {})
    }
  }, [profile, fetchProfile])

  const clearProfile = useCallback(() => {
    if (typeof window !== 'undefined') {
      localStorage.removeItem(LS_KEYS.PROFILE)
    }
    setProfile(null)
  }, [])

  return {
    profile,
    loading,
    error,
    refresh: fetchProfile,
    clearProfile
  }
}
