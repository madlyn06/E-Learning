import Footer from '@/components/footers/Footer'
import Header from '@/components/headers/Header'
import PageTitle from '@/components/shop/PageTitle'

import ShopSingle from '@/components/shop/ShopSingle'
import { shopItems } from '@/data/products'
import Head from 'next/head'
import React from 'react'
import { useRouter } from 'next/router'

export default function Page() {
  const { query } = useRouter()
  const product = shopItems.filter((elm) => elm.id == query.id)[0] || shopItems[0]
  return (
    <>
      <Head>
        <title>Shop Single || UpSkill - Education Online Courses LMS React Nextjs Template</title>
        <meta name='description' content='UpSkill - Education Online Courses LMS React Nextjs Template' />
      </Head>
      <div id='wrapper'>
        <div className='tf-top-bar flex items-center justify-center'>
          <p>Intro price. Get UpSkill for Big Sale -95% off.</p>
        </div>

        <Header />
        <PageTitle />
        <ShopSingle product={product} />
        <Footer parentClass='footer has-border-top' />
      </div>
    </>
  )
}
