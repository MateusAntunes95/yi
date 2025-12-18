import React, { useEffect, useState } from 'react'
import {
  CTable,
  CTableHead,
  CTableRow,
  CTableHeaderCell,
  CTableBody,
  CTableDataCell,
  CCard,
  CCardBody,
  CCardHeader,
} from '@coreui/react'
import axios from 'axios'

const Teams = () => {
  const [teams, setTeams] = useState([])

  useEffect(() => {
    axios.get('http://localhost:8000/teams')
      .then(res => setTeams(res.data))
      .catch(err => console.error(err))
  }, [])

  return (
    <CCard>
      <CCardHeader>Times</CCardHeader>
      <CCardBody>
        <CTable hover responsive>
          <CTableHead>
            <CTableRow>
              <CTableHeaderCell>Nome</CTableHeaderCell>
              <CTableHeaderCell>País</CTableHeaderCell>
              <CTableHeaderCell>Fundação</CTableHeaderCell>
            </CTableRow>
          </CTableHead>
          <CTableBody>
            {teams.map((team) => (
              <CTableRow key={team.id}>
                <CTableDataCell>{team.name}</CTableDataCell>
                <CTableDataCell>{team.country}</CTableDataCell>
                <CTableDataCell>{team.founded}</CTableDataCell>
              </CTableRow>
            ))}
          </CTableBody>
        </CTable>
      </CCardBody>
    </CCard>
  )
}

export default Teams
