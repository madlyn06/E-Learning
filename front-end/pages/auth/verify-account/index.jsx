import VerifyAccount from '@/components/auth/VerifyAccount'
import Head from 'next/head'

export default function VerifyEmailPage() {
  return (
    <>
      <Head>
        <title>Verify Account</title>
        <meta name="description" content="Verify your email address" />
      </Head>
      <div className='verifyEmailContainer'>
        <VerifyAccount />
      </div>
    </>
  )
}
