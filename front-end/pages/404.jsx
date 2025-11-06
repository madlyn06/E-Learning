import Footer from '@/components/footers/Footer'
import Header from '@/components/headers/Header'
import Head from 'next/head'
import Image from 'next/image'

import Link from 'next/link'
import React from 'react'

export default function page() {
  return (
    <>
      <Head>
        <title>Page Not Found || UpSkill - Education Online Courses LMS React Nextjs Template</title>
        <meta name='description' content='UpSkill - Education Online Courses LMS React Nextjs Template' />
      </Head>
      <div id='wrapper'>
        <div className='tf-top-bar flex items-center justify-center'>
          <p>Intro price. Get UpSkill for Big Sale -95% off.</p>
        </div>

        <Header />

        <div className='main-content page-404'>
          {/* section 404 */}
          <section className='page-404-wrap'>
            <div className='tf-container'>
              <div className='row'>
                <div className='col-lg-8'>
                  <div className='thumds'>
                    <Image
                      className='ls-is-cached lazyloaded'
                      data-src='/images/section/404.png'
                      alt=''
                      src='/images/section/404.png'
                      width={1536}
                      height={1236}
                    />
                  </div>
                </div>
                <div className='col-lg-4 flex items-center'>
                  <div className='errors-404-content'>
                    <h3>Oops! It looks like you&apos;re lost.</h3>
                    <p>The page you&apos;re looking for isn&apos;t available. Try to search again or use the go to.</p>
                    <Link className='tf-btn' href={`/`}>
                      Go Back To Homepage <i className='icon-arrow-top-right' />
                    </Link>
                  </div>
                </div>
              </div>
            </div>
          </section>
          {/* /section 404 */}
        </div>

        <Footer />
      </div>
    </>
  )
}
