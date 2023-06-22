<?php

namespace App\Controller\Traits;

trait PaginationTrait
{
    protected function getPaginationLimitAndOffset(int $page, int $itemsPerPage = 10): array
    {
        $limit = $itemsPerPage;
        $offset = --$page * 10;

        return [$limit, $offset];
    }

    protected function getPaginationMaxPageNumber(int $totalCount, int $itemsPerPage = 10): int
    {
        return (int) ceil($totalCount / $itemsPerPage);
    }

    protected function wrapWithPaginationData(
        array $data,
        int $totalCount,
        int $currentPage,
        string $targetRoute
    ): array {
        $result = [];

        $result['data'] = $data;
        $result['links'] = [];

        $result['links'][] = [
            'url' => $currentPage === 1
                ? null
                : $this->generateUrl($targetRoute, ['page' => $currentPage - 1]),
            'label' => 'Previous',
            'active' => false
        ];

        $maxPageNumber = $this->getPaginationMaxPageNumber($totalCount);

        // TODO: If pages are more than 5, show the previous, next
        for ($i = 1; $i <= $maxPageNumber; $i++) {
            $result['links'][] = [
                'url' => $this->generateUrl($targetRoute, ['page' => $i]),
                'label' => (string) $i,
                'active' => $currentPage === $i
            ];
        }

        $result['links'][] = [
            'url' => ($currentPage === $maxPageNumber || $maxPageNumber === 0)
                ? null
                : $this->generateUrl($targetRoute, ['page' => $currentPage + 1]),
            'label' => 'Next',
            'active' => false
        ];

        return $result;
    }
}
