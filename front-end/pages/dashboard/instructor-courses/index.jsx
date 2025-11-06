import InstructorCourses from '@/components/dashboard/instructor/InstructorCourses'
import Layout from '@/components/layouts'
import { courseApi } from '@/lib/api/dashboard/courseApi'
import { serverSideTranslations } from 'next-i18next/serverSideTranslations'
import React from 'react'
import { SWRConfig } from 'swr'

export default function Page({ fallback }) {
  return (
    <>
      <SWRConfig value={{ fallback }}>
        <Layout title='Instructor Courses' showPageTitle isDashboard>
          <InstructorCourses />
        </Layout>
      </SWRConfig>
    </>
  )
}
export async function getServerSideProps(context) {
  const cookies = context.req.cookies
  const token = cookies['token']

  const courses = await courseApi.getByInstructorId(token)
  
  return {
    props: {
      fallback: {
        '/v1/elearning/instructor-courses?page=1': courses
      },
      ...(await serverSideTranslations(context.locale, ['common', 'instructor']))
    }
  }
}
