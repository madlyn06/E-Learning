import React from 'react'
import Link from 'next/link'
import { useSearchParams } from 'next/navigation'
import { useReverifyEmail } from '@/hooks/useReverifyEmail'

function VerifyAccountPage() {
  const searchParams = useSearchParams()
  const email = searchParams.get('email')

  const reverifyEmail = useReverifyEmail()

  const reSendEmail = async () => {
    reverifyEmail(email)
  }
  return (
    <div className='verifyEmailCard'>
      <div className='content'>
        <h1 className='title'>Check Your Email</h1>
        <p className='message'>
          Please check your email inbox to verify your account. We have sent a verification link to the email address
          you registered with.
        </p>
        <p className='subMessage'>If you don&apos;t see the email, please check your spam or junk folder.</p>
      </div>

      <div className='actions'>
        <Link href='/auth/login' className='backToLoginBtn'>
          Back to Login
        </Link>
      </div>

      <div className='helpText'>
        <p>
          Didn&apos;t receive the email?{' '}
          <button className='resendBtn' onClick={reSendEmail}>
            Resend
          </button>
        </p>
      </div>
    </div>
  )
}

export default VerifyAccountPage
