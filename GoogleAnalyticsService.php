<?php

class GoogleAnalyticsService
{
    /**
     * Fetches Google Analytics API and converts it to a PHP ArrayObject.
     *
     * @param string $service_account   Service Account ID from GA Dashboard
     * @param string $key_file_location relative path to .p12 key file
     * @param string $profileId         GA View ID
     * @param string $start             beginning of date range in Y-m-d format
     * @param string $end               end of date range in Y-m-d format
     *
     * @return int
     */
    public function getVisitData($service_account, $key_file_location, $profileId, $start, $end)
    {
        require_once 'gapi.class.php';

        $dimensions = array('browser');
        $metrics = array('visits');
        $sortMetric = null;
        $filter = null;
        $startDate = $start;
        $endDate = $end;
        $startIndex = 1;
        $maxResults = 10000;

        $ga = new gapi($service_account, $key_file_location);

        $ga->requestReportData($profileId, $dimensions, $metrics, $sortMetric, $filter, $startDate, $endDate, $startIndex, $maxResults);

        foreach ($ga->getResults() as $result) {
            $visits = $result->getVisits();
        }

        return $visits;
    }

    /**
     * Calls Google Analytics Managment API to create new Web Properties
     * https://developers.google.com/analytics/devguides/config/mgmt/v3/mgmtReference/.
     */
    public function createNewProperty()
    {
        require_once __DIR__.'/vendor/autoload.php';

        try {
            $property = new Google_Service_Analytics_Webproperty();
            $property->setName('Example Store');
            $analytics->management_webproperties->insert('123456', $property);
        } catch (apiServiceException $e) {
            echo 'There was an Analytics API service error '
              .$e->getCode().':'.$e->getMessage();
        } catch (apiException $e) {
            echo 'There was a general API error '
              .$e->getCode().':'.$e->getMessage();
        }
    }
}
