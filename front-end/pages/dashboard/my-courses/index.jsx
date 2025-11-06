import MyCourses from '@/components/dashboard/MyCourses'
import Layout from '@/components/layouts'
import React from 'react'

export default function Page() {
  return (
    <>
      <Layout title='Instractor My Course' showPageTitle isDashboard>
        <MyCourses />
      </Layout>
    </>
  )
}
