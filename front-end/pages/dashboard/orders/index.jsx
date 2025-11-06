import Orders from '@/components/dashboard/Orders'
import Layout from '@/components/layouts'
import React from 'react'

export default function Page() {
  return (
    <>
      <Layout title='Student Orders' showPageTitle isDashboard>
        <Orders />
      </Layout>
    </>
  )
}
