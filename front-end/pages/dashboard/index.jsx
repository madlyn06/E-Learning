import Dashboard from '@/components/dashboard/Dashboard'
import Layout from '@/components/layouts'
import React from 'react'
import { serverSideTranslations } from 'next-i18next/serverSideTranslations'
import { courseApi } from '@/lib/api/dashboard/courseApi'

export default function Page({ bestSellingCourses, summaryCourses }) {
  return (
    <>
      <Layout title={'Instractor Dashboard'} showPageTitle isDashboard>
        <Dashboard courses={bestSellingCourses.data} summaryCourses={summaryCourses.data} />
      </Layout>
    </>
  )
}

export async function getServerSideProps(context) {
  const cookies = context.req.cookies
  const token = cookies['token']

  const bestSellingCourses = await courseApi.getCourseBestSelling(token)
  const summaryCourses = await courseApi.getSummaryCourses(token)
  return {
    props: {
      ...(await serverSideTranslations(context.locale, ['common'])),
      bestSellingCourses,
      summaryCourses
    }
  }
}
