import { CourseDetail } from '@/components/dashboard/instructor/CourseDetail'
import Layout from '@/components/layouts'
import { serverSideTranslations } from 'next-i18next/serverSideTranslations'
import React from 'react'

export default function Page() {
  return (
    <>
      <Layout title='Instructor Course' showPageTitle isDashboard>
        <CourseDetail />
      </Layout>
    </>
  )
}

export const getServerSideProps = async ({ locale }) => {
  return {
    props: {
      ...(await serverSideTranslations(locale, ['instructor', 'common']))
    }
  }
}
