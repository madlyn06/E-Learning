import Blogs1 from '@/components/blogs/Blogs1'
import PageTitle from '@/components/blogs/PageTitle'
import Footer from '@/components/footers/Footer'
import Header from '@/components/headers/Header'
import { serverSideTranslations } from 'next-i18next/serverSideTranslations'
import Head from 'next/head'
import React from 'react'

export default function page() {
  return (
    <>
      <Head>
        <title>Blog Grid || UpSkill - Education Online Courses LMS React Nextjs Template</title>
        <meta name='description' content='UpSkill - Education Online Courses LMS React Nextjs Template' />
      </Head>
      <div id='wrapper'>
        <div className='tf-top-bar flex items-center justify-center'>
          <p>Intro price. Get UpSkill for Big Sale -95% off.</p>
        </div>

        <Header />
        <PageTitle />
        <Blogs1 />
        <Footer parentClass='footer has-border-top' />
      </div>
    </>
  )
}

export async function getStaticProps({ locale }) {
  return {
    props: {
      ...(await serverSideTranslations(locale, ['common']))
    }
  }
}