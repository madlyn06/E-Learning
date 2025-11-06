import Footer from '@/components/footers/Footer'
import Header from '@/components/headers/Header'
import ForgotPassword from '@/components/auth/ForgotPassword'

import Head from 'next/head'
import React from 'react'
import Layout from '@/components/layouts'

export default function Page() {
  return (
    <>
      <Layout title='Forgot Password'>
        <ForgotPassword />
      </Layout>
    </>
  )
}
