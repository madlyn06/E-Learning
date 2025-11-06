import { NextResponse } from 'next/server'

const privatePaths = ['/me']
const authPaths = [
  '/auth/login',
  '/auth/register',
  '/auth/verify-account',
  '/auth/forgot-password',
  '/auth/verify-success',
  '/auth/reset-password'
]

export function middleware(req) {
  const { pathname } = req.nextUrl
  const { nextUrl } = req
  const locale = nextUrl.locale || 'vi'
  const token = req.cookies.get('token')?.value

  // Chưa đăng nhập thì không cho vào private paths
  // if (privatePaths.some((path) => pathname.startsWith(path)) && !token) {
  //   return NextResponse.redirect(new URL('/auth/login', req.url))
  // }

  // Đăng nhập rồi thì không cho vào login/register nữa
  if (authPaths.some((path) => pathname.startsWith(path)) && token) {
    return NextResponse.redirect(new URL('/', req.url))
  }

  return NextResponse.next()
}

export const config = {
  matcher: ['/((?!api|_next|.*\\..*).*)']
}
