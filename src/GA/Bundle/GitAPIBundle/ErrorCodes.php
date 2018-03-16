<?php
namespace GA\Bundle\GitAPIBundle;

interface ErrorCodes
{
    const ClientArgumentError = 2001;
    const ServerOffline = 1001;
    const RepositoryNotFound = 1002;
    const APIError = 1003;
}
