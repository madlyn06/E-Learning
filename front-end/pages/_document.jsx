import { Html, Head, Main, NextScript } from 'next/document'

export default function Document() {
  return (
    <Html lang='en'>
      <Head>
        <link rel='icon' href='/icons/logo.ico' />
      </Head>
      <body className='counter-scroll'>
        <Main />
        <NextScript />
      </body>
    </Html>
  )
}
