import Image from 'next/image'
import Link from 'next/link'
import ResetPasswordForm from './ResetPasswordForm'

export default function ResetPasswordPage() {
  return (
    <div className='main-content page-login'>
      <section className='section-page-login login-wrap tf-spacing-4'>
        <div className='tf-container'>
          <div className='row'>
            <div className='col-lg-6'>
              <div className='img-left wow fadeInLeft' data-wow-delay='0s'>
                <Image
                  className='ls-is-cached lazyloaded'
                  data-src=''
                  alt=''
                  src='/images/page-title/page-title-home2-1.jpg'
                  width={591}
                  height={680}
                />
                <div className='blockquite wow fadeInLeft' data-wow-delay='0.1s'>
                  <p>
                    Happiness prosperous impression had conviction For every delay <br />
                    in they
                  </p>
                  <p className='author'>Ali Tufan</p>
                  <p className='sub-author'>Founder &amp; CEO</p>
                </div>
              </div>
            </div>
            <div className='col-lg-6'>
              <div className='content-right'>
                <h2 className='login-title fw-7 wow fadeInUp' data-wow-delay='0s'>
                  Reset Password
                </h2>
                <div className='register'>
                  <p className='fw-5 fs-15 wow fadeInUp' data-wow-delay='0s'>
                    Don’t have an account?
                  </p>
                  <Link href='/auth/register' className='fw-5 fs-15 wow fadeInUp' data-wow-delay='0s'>
                    Join here
                  </Link>
                </div>
                <ResetPasswordForm />
              </div>
            </div>
          </div>
        </div>
        {/* <div className="login-wrap-content"></div> */}
      </section>
    </div>
  )
}
