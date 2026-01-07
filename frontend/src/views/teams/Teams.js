import React, { useEffect, useState } from 'react'
import axios from 'axios'
import UITable from '../base/tables/UITable'
import { CCard, CCardBody } from '@coreui/react'

const Teams = () => {
  const [header, setHeader] = useState([])
  const [clubs, setClubs] = useState([])

  // 1️⃣ Carrega clubes e header (SEM JSON.parse)
  useEffect(() => {
    axios.get('http://localhost:8080/club')
      .then(res => {
        if (!res.data.success) return

        setHeader(res.data.header)
        setClubs(res.data.clubs)
      })
      .catch(err => console.error(err))
  }, [])

  // 2️⃣ Busca campo específico ao clicar no "?"
  const handleFetchField = async (clubName, field) => {
    try {
      const res = await axios.get(
        `http://localhost:8080/club/detail/${clubName}/${field}`
      )

      if (res.data.success) {
        setClubs(prev =>
          prev.map(club =>
            club.name === clubName
              ? { ...club, [field]: res.data.value }
              : club
          )
        )
      } else {
        alert(res.data.message)
      }
    } catch (err) {
      console.error(err)
      alert('Erro ao buscar o campo do clube')
    }
  }

  return (
    <CCard>
      <CCardBody>
        {header.length > 0 && (
          <UITable
            header={header}
            clubs={clubs}
            onFetchField={handleFetchField}
          />
        )}
      </CCardBody>
    </CCard>
  )
}

export default Teams
