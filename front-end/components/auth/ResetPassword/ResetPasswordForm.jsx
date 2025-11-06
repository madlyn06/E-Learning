import React from 'react'
import { Controller, useForm } from 'react-hook-form'
import { useRouter } from 'next/router'
import apiFetch from '@/utils/request'
import { toast } from 'react-toastify'
import { useSearchParams } from 'next/navigation'
import { useLoading } from '@/hooks/useLoading'
import { Input } from '@/components/ui/Input'
import { Button } from '@/components/ui/Button'

function ResetPasswordForm() {
  const {
    control,
    handleSubmit,
    watch,
    formState: { errors }
  } = useForm()

  const { loading, withLoading } = useLoading()

  const searchParams = useSearchParams()
  const token = searchParams.get('token')
  const email = searchParams.get('email')

  const router = useRouter()

  const onSubmit = handleSubmit(async (data) => {
    await withLoading(async () => {
      const { password, password_confirmation } = data
      try {
        await apiFetch.post('v1/elearning/auth/reset-password', {
          password_confirmation,
          password,
          token,
          email
        })
        toast.success('Reset password successfully')
        router.push('/auth/login')
      } catch (error) {
        toast.error('Reset password failed')
      }
    })
  })
  return (
    <form onSubmit={onSubmit} className='form-login'>
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
          render={({ field }) => <Input {...field} type='password' label='Password' error={errors.password?.message} />}
        />
      </div>
      <div className='cols my-4'>
        <Controller
          name='password_confirmation'
          control={control}
          rules={{
            required: 'Confirm password is required',
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
            <Input {...field} type='password' label='Confirm password' error={errors.password_confirmation?.message} />
          )}
        />
      </div>

      <Button type='submit' className='w-100 my-4' disabled={loading}>
        Reset password
      </Button>
    </form>
  )
}

export default ResetPasswordForm
