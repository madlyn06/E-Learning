import Footer from "@/components/footers/Footer"
import Header from "@/components/headers/Header"

import React from "react"
import Link from "next/link"
import Faqs from "@/components/otherPages/faq/Faq"
import Head from "next/head"

export default function page() {
  return (
    <>
      <Head>
        <title>Faq || UpSkill - Education Online Courses LMS React Nextjs Template</title>
        <meta name="description" content="UpSkill - Education Online Courses LMS React Nextjs Template" />
      </Head>
      <div id="wrapper">
        <div className="tf-top-bar flex items-center justify-center">
          <p>Intro price. Get UpSkill for Big Sale -95% off.</p>
        </div>

        <Header />
        <div className="page-title page-pricing-title">
          <div className="tf-container">
            <div className="row">
              <div className="col-12">
                <div className="content text-center">
                  <ul className="breadcrumbs flex items-center justify-center gap-10">
                    <li>
                      <Link href={`/`} className="flex">
                        <i className="icon-home" />
                      </Link>
                    </li>
                    <li>
                      <i className="icon-arrow-right" />
                    </li>
                    <li>Pages</li>
                    <li>
                      <i className="icon-arrow-right" />
                    </li>
                    <li>Instructor</li>
                  </ul>
                  <h2 className="font-cardo fw-7">Frequently Asked Questions</h2>
                  <h6>We’re on a mission to deliver engaging, curated courses at a reasonable price.</h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="main-content ">
          <Faqs />
        </div>
        <Footer parentClass="footer has-border-top" />
      </div>
    </>
  )
}
