import { Input } from '@/components/ui/Input'
import { useLoading } from '@/hooks/useLoading'
import apiFetch from '@/utils/request'
import React from 'react'
import { Controller, useForm } from 'react-hook-form'
import { toast } from 'react-toastify'
import { useProfile } from '@/hooks/useProfile'

function Social() {
  const { loading, withLoading } = useLoading()
  const { refresh, profile } = useProfile()
  const {
    handleSubmit,
    formState: { errors },
    control
  } = useForm({
    defaultValues: {
      facebook: profile?.facebook || '',
      twitter: profile?.twitter || '',
      linkedin: profile?.linkedin || '',
      instagram: profile?.instagram || '',
      website: profile?.website || '',
      github: profile?.github || ''
    }
  })

  const onSubmit = handleSubmit(async (data) => {
    try {
      await withLoading(async () => {
        await apiFetch.put('v1/elearning/users/profile', {
          facebook: data.facebook,
          twitter: data.twitter,
          linkedin: data.linkedin,
          instagram: data.instagram,
          website: data.website,
          github: data.github
        })
        toast.success('Social updated successfully')
        refresh()
      })
    } catch (error) {
      console.log(error.message)
      toast.error('Social updated failed')
    }
  })
  return (
    <div className='widget-content-inner'>
      <form onSubmit={onSubmit} className='shop-checkout'>
        <Controller
          name='facebook'
          control={control}
          render={({ field }) => <Input label='Facebook' {...field} error={errors.facebook?.message} />}
        />
        <Controller
          name='twitter'
          control={control}
          render={({ field }) => <Input label='Twitter' {...field} error={errors.twitter?.message} />}
        />

        <Controller
          name='linkedin'
          control={control}
          render={({ field }) => <Input label='Linkedin' {...field} error={errors.linkedin?.message} />}
        />

        <Controller
          name='instagram'
          control={control}
          render={({ field }) => <Input label='Instagram' {...field} error={errors.instagram?.message} />}
        />
        <Controller
          name='website'
          control={control}
          render={({ field }) => <Input label='Website' {...field} error={errors.website?.message} />}
        />
        <Controller
          name='github'
          control={control}
          render={({ field }) => <Input label='Github' {...field} error={errors.github?.message} />}
        />
        <button type='submit' className='tf-btn' disabled={loading}>
          Update Social <i className='icon-arrow-top-right' />
        </button>
      </form>
    </div>
  )
}

export default Social
