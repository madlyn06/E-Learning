import Footer1 from '@/components/footers/Footer1'
import Header1 from '@/components/headers/Header1'

import Register from '@/components/otherPages/Register'
import Head from 'next/head'
import React from 'react'

export default function page() {
  return (
    <>
      <Head>
        <title>Register || UpSkill - Education Online Courses LMS React Nextjs Template</title>
        <meta name='description' content='UpSkill - Education Online Courses LMS React Nextjs Template' />
      </Head>
      <div id='wrapper'>
        <div className='tf-top-bar flex items-center justify-center'>
          <p>Intro price. Get UpSkill for Big Sale -95% off.</p>
        </div>

        <Header1 />

        <Register />

        <Footer1 />
      </div>
    </>
  )
}
