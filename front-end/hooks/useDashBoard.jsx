import apiFetch from '@/utils/request'
import useSWR from 'swr'
import { fetcher } from '@/utils/fetcher'
export function useDashBoard() {
  const useFetchCategories = () => {
    const { data, error, isLoading, mutate } = useSWR('/v1/elearning/categories', fetcher, {
      revalidateOnFocus: false,
      revalidateOnReconnect: false,
      revalidateIfStale: false,
      dedupingInterval: 60000 * 60,
      focusThrottleInterval: 60000 * 60
    })

    return [{ data, error, isLoading, mutate }]
  }

  const useFetchLevel = () => {
    const { data, error, isLoading, mutate } = useSWR('/v1/elearning/categories', fetcher)

    return [{ data, error, isLoading, mutate }]
  }

  return { useFetchCategories, useFetchLevel }
}
