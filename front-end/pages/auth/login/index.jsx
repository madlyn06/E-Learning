import Login from '@/components/auth/Login'
import React from 'react'
import Layout from '@/components/layouts'

export default function page() {
  return (
    <>
      <Layout title={'Login'}>
        <Login />
      </Layout>
    </>
  )
}
