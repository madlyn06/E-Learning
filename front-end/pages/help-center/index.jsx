import Footer from "@/components/footers/Footer"
import Header from "@/components/headers/Header"
import Services from "@/components/otherPages/help-center/Services"

import React from "react"
import Link from "next/link"
import Head from "next/head"
import Faqs from "@/components/otherPages/faq/Faq"

export default function page() {
  return (
    <>
      <Head>
        <title>Help Center || UpSkill - Education Online Courses LMS React Nextjs Template</title>
        <meta name="description" content="UpSkill - Education Online Courses LMS React Nextjs Template" />
      </Head>
      <div id="wrapper">
        <div className="tf-top-bar flex items-center justify-center">
          <p>Intro price. Get UpSkill for Big Sale -95% off.</p>
        </div>

        <Header />
        <div className="page-title page-help">
          <div className="tf-container full">
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
                  <h2 className="font-cardo fw-7">Help Center</h2>
                  <h6>We’re on a mission to deliver engaging, curated courses at a reasonable price.</h6>
                  <form className="form-search-courses">
                    <div className="icon">
                      <i className="icon-keyboard" />
                    </div>
                    <fieldset>
                      <input
                        className=""
                        type="text"
                        placeholder="Cancellation, meeting point, etc."
                        name="text"
                        tabIndex={2}
                        defaultValue=""
                        aria-required="true"
                        required
                      />
                    </fieldset>
                    <div className="button-submit">
                      <button className="">
                        <i className="icon-search fs-20" />
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="main-content pt-0">
          <Services />
          <Faqs />
        </div>
        <Footer />
      </div>
    </>
  )
}
