import CourseList2 from '@/components/course-list/CourseList2'
import PageTitle from '@/components/course-list/PageTitle'
import Footer1 from '@/components/footers/Footer1'
import Header1 from '@/components/headers/Header1'
import Head from 'next/head'
import React from 'react'

export default function page() {
  return (
    <>
      <Head>
        <title>Course Grid Modern || UpSkill - Education Online Courses LMS React Nextjs Template</title>
        <meta name='description' content='UpSkill - Education Online Courses LMS React Nextjs Template' />
      </Head>
      <div id='wrapper'>
        <div className='tf-top-bar flex items-center justify-center'>
          <p>Intro price. Get UpSkill for Big Sale -95% off.</p>
        </div>

        <Header1 />
        <PageTitle parentClass='page-title style-3 has-tags-bg-sky' />

        <CourseList2 />

        <Footer1 parentClass='footer has-border-top' />
      </div>
    </>
  )
}
