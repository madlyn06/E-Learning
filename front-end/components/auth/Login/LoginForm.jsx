import React, { useState } from 'react'
import { setToken } from '@/utils/auth'
import { useAuth } from '@/context/AuthContext'
import { toast } from 'react-toastify'
import { HTTP_STATUS } from '@/utils/config'
import { Input } from '@/components/ui/Input'
import { Button } from '@/components/ui/Button'
import { Controller, useForm } from 'react-hook-form'
import { useRouter } from 'next/router'
import apiFetch from '@/utils/request'
import Link from 'next/link'
import { useReverifyEmail } from '@/hooks/useReverifyEmail'

function LoginForm() {
  const [isVerifyAccount, setIsVerifyAccount] = useState(false)
  const [email, setEmail] = useState('')

  const reverifyEmail = useReverifyEmail()

  const {
    control,
    handleSubmit,
    formState: { errors }
  } = useForm()

  const { login } = useAuth()

  const router = useRouter()

  const onSubmit = handleSubmit(async (data) => {
    const { email, password } = data
    try {
      setEmail(email)
      setIsVerifyAccount(false)
      const data = await apiFetch.post('v1/elearning/auth/login', { email, password })
      const { token, user } = data
      setToken(token)
      login(user, token)
      router.push('/')
      toast.success('Login successfully')
    } catch (error) {
      const errMessage = error?.payload?.message || 'Login failed'
      if (error.status === HTTP_STATUS.UNAUTHORIZED || error.status === HTTP_STATUS.FORBIDDEN) {
        toast.error(errMessage)
        if (errMessage === 'Email not verified') {
          setIsVerifyAccount(true)
        }
        return
      }

      toast.error(errMessage)
    }
  })

  const handleReverifyEmail = async (email) => {
    reverifyEmail(email)
  }
  return (
    <form onSubmit={onSubmit} className='form-login'>
      <div className='cols'>
        <Controller
          name='email'
          control={control}
          rules={{
            required: 'Email is required',
            pattern: {
              value: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
              message: 'Invalid email address'
            }
          }}
          render={({ field }) => (
            <Input
              {...field}
              {...field}
              onChange={(e) => {
                field.onChange(e)
                isVerifyAccount && setIsVerifyAccount(false)
              }}
              label='Email'
              error={errors.email?.message}
              className='field-username'
            />
          )}
        />
      </div>
      <div className='cols'>
        <Controller
          name='password'
          control={control}
          rules={{
            required: 'Password is required',
            minLength: {
              value: 6,
              message: 'Password must be at least 6 characters'
            }
          }}
          render={({ field }) => (
            <Input
              {...field}
              onChange={(e) => {
                field.onChange(e)
                isVerifyAccount && setIsVerifyAccount(false)
              }}
              label='Password'
              error={errors.password?.message}
              className='field-pass'
              type='password'
            />
          )}
        />
      </div>
      <div className='checkbox-item'>
        <label className='wow fadeInUp' data-wow-delay='0s'>
          <p className='fs-15'>Remember me</p>
          <input type='checkbox' />
          <span className='btn-checkbox' />
        </label>
        <Link href='/auth/forgot-password' className='fs-15 wow fadeInUp' data-wow-delay='0.1s'>
          Forgot your password?
        </Link>
      </div>

      {isVerifyAccount && (
        <div className='checkbox-item'>
          <Button className='verify-account w-50' onClick={() => handleReverifyEmail(email)}>
            Verify account?
          </Button>
        </div>
      )}

      <Button type='submit'>Log In</Button>
    </form>
  )
}

export default LoginForm
