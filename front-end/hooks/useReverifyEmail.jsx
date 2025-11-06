import apiFetch from '@/utils/request'
import { useCallback } from 'react'
import { toast } from 'react-toastify'

export const useReverifyEmail = () => {
  const reverifyEmail = useCallback(async (email) => {
    try {
      await apiFetch.post('/v1/elearning/auth/resend-email-verify', { email })
      toast.success('Resend email verify successfully')
    } catch (error) {
      toast.error(error?.payload?.message || 'Resend email verify failed')
    }
  }, [])

  return reverifyEmail
}
