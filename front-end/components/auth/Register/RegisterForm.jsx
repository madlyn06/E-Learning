import React from 'react'
import { Controller, useForm } from 'react-hook-form'
import { useRouter } from 'next/router'
import apiFetch from '@/utils/request'
import { toast } from 'react-toastify'
import { useLoading } from '@/hooks/useLoading'
import { Input } from '@/components/ui/Input'
import { Button } from '@/components/ui/Button'

function RegisterForm() {
  const {
    handleSubmit,
    watch,
    formState: { errors },
    control
  } = useForm()

  const router = useRouter()

  const { withLoading, loading } = useLoading()

  const onSubmit = async (data) => {
    await withLoading(async () => {
      const { name, email, password } = data
      try {
        const data = await apiFetch.post('v1/elearning/auth/register', { name, email, password })
        const { token } = data

        router.push(`/auth/verify-account?token=${encodeURIComponent(token)}&email=${encodeURIComponent(email)}`)

        toast.success('Register successfully')
      } catch (error) {
        toast.error('Register failed')
      }
    })
  }
  return (
    <form onSubmit={handleSubmit(onSubmit)} className='form-login'>
      <div className='cols'>
        <Controller
          control={control}
          name='name'
          rules={{
            required: 'Username is required',
            minLength: {
              value: 6,
              message: 'Username must be at least 6 characters'
            }
          }}
          render={({ field }) => (
            <Input className='field-username wow fadeInUp' {...field} error={errors.name?.message} label='Username' />
          )}
        />
      </div>
      <div className='cols'>
        <Controller
          control={control}
          name='email'
          rules={{
            required: 'Email is required',
            pattern: {
              value: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
              message: 'Invalid email address'
            }
          }}
          render={({ field }) => (
            <Input
              className='field-username wow fadeInUp'
              {...field}
              error={errors.email?.message}
              label='Email'
              type='email'
            />
          )}
        />
      </div>
      <div className='cols'>
        <Controller
          control={control}
          name='password'
          rules={{
            required: 'Password is required',
            minLength: {
              value: 6,
              message: 'Password must be at least 6 characters'
            }
          }}
          render={({ field }) => (
            <Input
              className='field-username wow fadeInUp'
              {...field}
              error={errors.password?.message}
              label='Password'
              type='password'
            />
          )}
        />
      </div>
      <div className='cols'>
        <Controller
          control={control}
          name='passwordAgain'
          rules={{
            required: 'Password is required',
            minLength: {
              value: 6,
              message: 'Password must be at least 6 characters'
            },
            validate: (value) => {
              if (value !== watch('password')) {
                return 'Passwords do not match'
              }
              return true
            }
          }}
          render={({ field }) => (
            <Input
              {...field}
              className='field-username wow fadeInUp'
              error={errors.passwordAgain?.message}
              label='Repeat Password'
              type='password'
            />
          )}
        />
      </div>
      <Button type='submit' disabled={loading}>
        Sign up
      </Button>
    </form>
  )
}

export default RegisterForm
