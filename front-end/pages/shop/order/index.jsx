import Footer from '@/components/footers/Footer'
import Header from '@/components/headers/Header'

import PageTitle from '@/components/shop/PageTitle'
import ShopOrder from '@/components/shop/ShopOrder'
import Head from 'next/head'

import React from 'react'

export default function page() {
  return (
    <>
      <Head>
        <title>Shop Order || UpSkill - Education Online Courses LMS React Nextjs Template</title>
        <meta name='description' content='UpSkill - Education Online Courses LMS React Nextjs Template' />
      </Head>
      <div id='wrapper'>
        <div className='tf-top-bar flex items-center justify-center'>
          <p>Intro price. Get UpSkill for Big Sale -95% off.</p>
        </div>

        <Header />
        <PageTitle />
        <ShopOrder />
        <Footer parentClass='footer has-border-top' />
      </div>
    </>
  )
}
