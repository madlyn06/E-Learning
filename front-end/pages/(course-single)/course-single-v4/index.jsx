import CourseSingle4 from '@/components/course-single/CourseSingle4'
import Footer1 from '@/components/footers/Footer1'
import Header1 from '@/components/headers/Header1'
import Head from 'next/head'
import React from 'react'

export default function page() {
  return (
    <>
      <Head>
        <title>Course Single 4 || UpSkill - Education Online Courses LMS React Nextjs Template</title>
        <meta name='description' content='UpSkill - Education Online Courses LMS React Nextjs Template' />
      </Head>
      <div id='wrapper'>
        <Header1 />
        <div className='main-content pt-0'>
          <CourseSingle4 />
        </div>
        <Footer1 />
      </div>
    </>
  )
}
