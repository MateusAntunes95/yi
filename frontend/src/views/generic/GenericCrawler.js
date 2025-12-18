import React, { useState } from 'react'
import {
  CCard,
  CCardBody,
  CCardHeader,
  CButton,
  CForm,
  CFormInput,
  CFormTextarea,
} from '@coreui/react'
import axios from 'axios'

const GenericCrawler = () => {
  const [url, setUrl] = useState('')
  const [selector, setSelector] = useState('')
  const [result, setResult] = useState('')

  const handleSubmit = async (e) => {
    e.preventDefault()

    const res = await axios.post('http://localhost:8000/generic/extract', {
      url,
      selectors: {
        value: selector,
      },
    })

    setResult(res.data.data.value ?? '')
  }

  return (
    <CCard>
      <CCardHeader>Crawler</CCardHeader>
      <CCardBody>
        <CForm onSubmit={handleSubmit}>
          <CFormInput
            label="URL"
            value={url}
            onChange={(e) => setUrl(e.target.value)}
            className="mb-3"
          />

          <CFormInput
            label="CSS Selector"
            value={selector}
            onChange={(e) => setSelector(e.target.value)}
            className="mb-3"
          />

          <CButton type="submit">Buscar</CButton>
        </CForm>

        <CFormTextarea
          label="Resultado"
          value={result}
          rows={4}
          className="mt-3"
          readOnly
        />
      </CCardBody>
    </CCard>
  )
}

export default GenericCrawler
