import useSWR from 'swr'
import { fetcher } from '@/utils/fetcher'
import { useAuth } from '@/context/AuthContext'
export function useInstructor() {
  const useGetProfile = () => {
    const { setProfile } = useAuth()
    const { data, error, isLoading, mutate } = useSWR('/v1/elearning/users/profile', fetcher, {
      revalidateOnFocus: false,
      revalidateOnReconnect: false,
      revalidateIfStale: false,
      dedupingInterval: 60000 * 60,
      focusThrottleInterval: 60000 * 60
    })

    if (data) {
      setProfile(data?.user)
    }

    return [{ data, error, isLoading, mutate }]
  }

  return { useGetProfile }
}
