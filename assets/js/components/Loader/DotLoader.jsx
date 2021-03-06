import React from 'react'
import ContentLoader from 'react-content-loader'

const DotLoader = props => (
  <ContentLoader
    viewBox="0 0 400 160"
    height={320}
    width={800}
    backgroundColor="transparent"
    {...props}
  >
    <circle cx="150" cy="86" r="8" />
    <circle cx="194" cy="86" r="8" />
    <circle cx="238" cy="86" r="8" />
  </ContentLoader>
)

export default DotLoader;