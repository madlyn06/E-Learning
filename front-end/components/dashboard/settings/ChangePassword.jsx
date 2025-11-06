import apiFetch from '@/utils/request'
import { Input } from '../../ui/Input'
import React from 'react'
import { Controller, useForm } from 'react-hook-form'
import { toast } from 'react-toastify'
import { useLoading } from '@/hooks/useLoading'

function ChangePassword() {
  const { loading, withLoading } = useLoading()
  const {
    handleSubmit,
    formState: { errors },
    control,
    watch,
    reset
  } = useForm({
    defaultValues: {
      current_password: '',
      password: '',
      password_confirmation: ''
    }
  })

  const onSubmit = handleSubmit(async (data) => {
    try {
      await withLoading(async () => {
        await apiFetch.put('v1/elearning/users/profile', {
          current_password: data.current_password,
          password: data.password
        })
        toast.success('Password changed successfully')
        reset()
      })
    } catch (error) {
      console.log(error.message)
      toast.error('Password changed failed')
    }
  })

  return (
    <div className='widget-content-inner'>
      <form onSubmit={onSubmit} className='shop-checkout'>
        <Controller
          control={control}
          name='current_password'
          rules={{
            required: 'Password is required',
            minLength: { value: 6, message: 'Password must be at least 6 characters' }
          }}
          render={({ field }) => (
            <Input error={errors.current_password?.message} label='Current Password' type='password' {...field} />
          )}
        />

        <Controller
          control={control}
          name='password'
          rules={{
            required: 'Password is required',
            minLength: { value: 6, message: 'Password must be at least 6 characters' }
          }}
          render={({ field }) => (
            <Input error={errors.password?.message} label='New Password' type='password' {...field} />
          )}
        />

        <Controller
          control={control}
          name='password_confirmation'
          rules={{
            required: 'Password is required',
            minLength: { value: 6, message: 'Password must be at least 6 characters' },
            validate: (value) => value === watch('password') || 'Passwords do not match'
          }}
          render={({ field }) => (
            <Input
              error={errors.password_confirmation?.message}
              label='Re-Type New Password'
              type='password'
              {...field}
            />
          )}
        />
        <button type='submit' className='tf-btn' disabled={loading}>
          Update Password <i className='icon-arrow-top-right' />
        </button>
      </form>
    </div>
  )
}

export default ChangePassword
