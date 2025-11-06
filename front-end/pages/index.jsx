import Head from 'next/head'
import HomePage from './homes'

export default function Home() {
  return (
    <>
      <Head>
        <title>Education Online Courses</title>
        <meta name='description' content='Education Online Courses LMS' />
      </Head>
      <HomePage />
    </>
  )
}
