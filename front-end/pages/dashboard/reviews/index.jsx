import Reviews from '@/components/dashboard/Reviews'
import Layout from '@/components/layouts'
import React from 'react'

export default function Page() {
  return (
    <>
      <Layout title='Instractor Reviews' showPageTitle isDashboard>
        <Reviews />
      </Layout>
    </>
  )
}
