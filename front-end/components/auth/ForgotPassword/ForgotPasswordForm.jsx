import { Input } from '@/components/ui/Input'
import { Button } from '@/components/ui/Button'
import { Controller, useForm } from 'react-hook-form'
import { useRouter } from 'next/router'
import apiFetch from '@/utils/request'
import { toast } from 'react-toastify'
import { useLoading } from '@/hooks/useLoading'

function ForgotPasswordForm() {
  const {
    control,
    handleSubmit,
    formState: { errors }
  } = useForm()

  const router = useRouter()
  const { loading, withLoading } = useLoading()

  const onSubmit = handleSubmit(async (data) => {
    await withLoading(async () => {
      const { email } = data
      try {
        await apiFetch.post('v1/elearning/auth/forgot-password', { email })
        toast.success('Reset password email sent')
        router.push('/auth/login')
      } catch (error) {
        toast.error('Failed to send reset password email')
      }
    })
  })
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
          render={({ field }) => <Input {...field} label='Email' error={errors.email?.message} />}
        />
      </div>

      <Button className='w-100 my-4' type='submit' disabled={loading}>
        Send reset password email
      </Button>
    </form>
  )
}

export default ForgotPasswordForm
