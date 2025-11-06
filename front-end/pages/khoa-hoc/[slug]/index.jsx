import CourseSingle1 from '@/components/course-single/CourseSingle1'
import Footer from '@/components/footers/Footer'
import Header from '@/components/headers/Header'
import { allCourses } from '@/data/courese'
import Head from 'next/head'
import React from 'react'
import { useRouter } from 'next/router'

export default function Page() {
  const { query } = useRouter()
  const courseItem = allCourses.filter((elm) => elm.id == query.id)[0] || allCourses[0]
  return (
    <>
      <Head>
        <title>Course Single 1 || UpSkill - Education Online Courses LMS React Nextjs Template</title>
        <meta name='description' content='UpSkill - Education Online Courses LMS React Nextjs Template' />
      </Head>
      <div id='wrapper'>
        <div className='tf-top-bar flex items-center justify-center'>
          <p>Intro price. Get UpSkill for Big Sale -95% off.</p>
        </div>

        <Header />
        <div className='main-content pt-0'>
          <CourseSingle1 courseItem={courseItem} />
        </div>
        <Footer />
      </div>
    </>
  )
}
