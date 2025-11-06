import { useEffect, useState } from 'react'
import { useRouter, useSearchParams } from 'next/navigation'
import apiFetch from '@/utils/request'
import { toast } from 'react-toastify'
import Loading from '@/components/ui/Loading'
import { useAuth } from '@/context/AuthContext'
import { useReverifyEmail } from '@/hooks/useReverifyEmail'

function VerifySuccessPage() {
  const [countdown, setCountdown] = useState(5)
  const [status, setStatus] = useState('loading')

  const router = useRouter()

  const searchParams = useSearchParams()
  const token = searchParams.get('token')
  const email = searchParams.get('email')

  const { login } = useAuth()

  const reverifyEmail = useReverifyEmail()

  useEffect(() => {
    async function verifyEmail() {
      if (!token) return
      try {
        const data = await apiFetch.post(`v1/elearning/users/verify-account`, null, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        })
        login(data.user, token)
        setStatus('success')
        toast.success('Verify account successfully')
      } catch (err) {
        setStatus('error')
        toast.error('Verify account failed')
      }
    }

    verifyEmail()
  }, [token])

  useEffect(() => {
    if (status === 'success') {
      const timer = setInterval(() => {
        setCountdown((prev) => {
          if (prev <= 1) {
            clearInterval(timer)
            router.push('/')
            return 0
          }
          return prev - 1
        })
      }, 1000)

      return () => clearInterval(timer)
    }
  }, [router, status])

  if (status === 'loading') {
    return <Loading />
  }

  const reSendEmail = async () => {
    reverifyEmail(email)
  }

  return (
    <div className='success-card'>
      <div className='success-icon'>
        {status === 'success' ? (
          <svg viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
            <circle cx='12' cy='12' r='10' stroke='currentColor' strokeWidth='2' />
            <path
              d='m9 12 2 2 4-4'
              stroke='currentColor'
              strokeWidth='2'
              strokeLinecap='round'
              strokeLinejoin='round'
            />
          </svg>
        ) : (
          <svg viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
            <circle cx='12' cy='12' r='10' stroke='currentColor' strokeWidth='2' />
            <line x1='15' y1='9' x2='9' y2='15' stroke='currentColor' strokeWidth='2' strokeLinecap='round' />
            <line x1='9' y1='9' x2='15' y2='15' stroke='currentColor' strokeWidth='2' strokeLinecap='round' />
          </svg>
        )}
      </div>

      <h1 className='success-title'>
        {status === 'success' ? 'Verify account successfully' : 'Verify account failed'}
      </h1>
      <p className='success-message'>
        {status === 'success'
          ? 'Your account has been activated successfully. You can now start using the service.'
          : 'Verify account failed. Please try again.'}
      </p>

      {status === 'success' ? (
        <div className='countdown-section'>
          <div className='countdown-circle'>
            <span className='countdown-number'>{countdown}</span>
          </div>
          <p className='countdown-text'>
            Redirect to login page in <strong>{countdown}</strong> seconds
          </p>
        </div>
      ) : (
        <div className='helpText'>
          <p>
            <button className='resendBtn' onClick={reSendEmail}>
              Resend
            </button>
          </p>
        </div>
      )}
    </div>
  )
}

export default VerifySuccessPage
