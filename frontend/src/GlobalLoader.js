import { CBackdrop, CSpinner } from '@coreui/react'
import { useLoading } from './LoadingContext'

const GlobalLoader = () => {
  const { loading } = useLoading()

  return (
    <CBackdrop visible={loading} className="bg-dark bg-opacity-50">
      <CSpinner color="light" />
    </CBackdrop>
  )
}

export default GlobalLoader
