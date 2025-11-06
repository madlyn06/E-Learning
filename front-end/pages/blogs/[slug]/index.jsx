import BlogSingle from '@/components/blogs/BlogSingle'
import Footer from '@/components/footers/Footer'
import Header from '@/components/headers/Header'
import Head from 'next/head'
import React from 'react'
import { useRouter } from 'next/router'
import apiFetch from '@/utils/request'

export async function getStaticPaths() {
  const [posts] = await Promise.all([
    apiFetch(`all-posts`),
  ]);

  const pathsPost = posts?.data.map((post) => ({
    params: { slug: post.slug.toString() },
  }));

  return {
    paths: pathsPost,
    fallback: 'blocking'
  };
}

export async function getStaticProps({ params }) {
  const [post, configs] = await Promise.all([
    apiFetch(`posts/${params.slug}`),
    apiFetch(`configs/${[
      'btn_follow_on_google',
      'value_follow_on_google',
      'logo',
      'site_contact_phone',
      'setting_facebook',
      'setting_instagram',
      'setting_linkedin',
      'setting_pinterest',
      'setting_twitter',
      'setting_youtube',
      'captcha_key',
      'site_title',
    ]}`)
  ]);
  return {
    props: {
      post,
      configs
    },
    revalidate: 10,
  };
}

export default function Page({ post, configs }) {
  
   const router = useRouter();
   if (router.isFallback) {
    return <div>Loading...</div>;
  }
  const blogItem = post.post

  return (
    <>
      <Head>
        <title>{blogItem.title}</title>
        <meta name='description' content={blogItem.description ?? blogItem.title} />
      </Head>
      <div id='wrapper'>
        <div className='tf-top-bar flex items-center justify-center'>
          <p>Intro price. Get UpSkill for Big Sale -95% off.</p>
        </div>

        <Header />

        <BlogSingle blogItem={blogItem} />
        <Footer parentClass='footer has-border-top' />
      </div>
    </>
  )
}
