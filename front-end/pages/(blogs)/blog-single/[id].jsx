import BlogSingle from '@/components/blogs/BlogSingle'

import Footer1 from '@/components/footers/Footer1'
import Header1 from '@/components/headers/Header1'
import { allBlogs } from '@/data/blogs'
import Head from 'next/head'
import React from 'react'
import { useRouter } from 'next/router'

export default function Page() {
  const { query } = useRouter()
  const blogItem = allBlogs.filter((elm) => elm.id == query.id)[0] || allBlogs[0]
  return (
    <>
      <Head>
        <title>Blog Single || UpSkill - Education Online Courses LMS React Nextjs Template</title>
        <meta name='description' content='UpSkill - Education Online Courses LMS React Nextjs Template' />
      </Head>
      <div id='wrapper'>
        <div className='tf-top-bar flex items-center justify-center'>
          <p>Intro price. Get UpSkill for Big Sale -95% off.</p>
        </div>

        <Header1 />

        <BlogSingle blogItem={blogItem} />
        <Footer1 parentClass='footer has-border-top' />
      </div>
    </>
  )
}
