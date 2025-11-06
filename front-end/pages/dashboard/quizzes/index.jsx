import Quiz from '@/components/dashboard/Quiz'
import Layout from '@/components/layouts'
import React from 'react'

export default function Page() {
  return (
    <>
      <Layout title='Instractor Quizes' showPageTitle isDashboard>
        <Quiz />
      </Layout>
    </>
  )
}
