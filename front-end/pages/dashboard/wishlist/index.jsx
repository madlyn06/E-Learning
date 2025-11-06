import Wishlist from '@/components/dashboard/Wishlist'
import Layout from '@/components/layouts'
import React from 'react'

export default function Page() {
  return (
    <>
      <Layout title='Instractor Wishlist' showPageTitle isDashboard>
        <Wishlist />
      </Layout>
    </>
  )
}
