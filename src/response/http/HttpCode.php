<?php

declare(strict_types=1);

namespace encritary\userQuests\response\http;

enum HttpCode: int{

	case Continue = 100;
	case SwitchingProtocols = 101;
	case Processing = 102;             // RFC2518
	case EarlyHints = 103;             // RFC8297
	case Ok = 200;
	case Created = 201;
	case Accepted = 202;
	case NonAuthoritativeInformation = 203;
	case NoContent = 204;
	case ResetContent = 205;
	case PartialContent = 206;
	case MultiStatus = 207;           // RFC4918
	case AlreadyReported = 208;       // RFC5842
	case ImUsed = 226;                // RFC3229
	case MultipleChoices = 300;
	case MovedPermanently = 301;
	case Found = 302;
	case SeeOther = 303;
	case NotModified = 304;
	case UseProxy = 305;
	case Reserved = 306;
	case TemporaryRedirect = 307;
	case PermanentlyRedirect = 308;   // RFC7238
	case BadRequest = 400;
	case Unauthorized = 401;
	case PaymentRequired = 402;
	case Forbidden = 403;
	case NotFound = 404;
	case MethodNotAllowed = 405;
	case NotAcceptable = 406;
	case ProxyAuthenticationRequired = 407;
	case RequestTimeout = 408;
	case Conflict = 409;
	case Gone = 410;
	case LengthRequired = 411;
	case PreconditionFailed = 412;
	case RequestEntityTooLarge = 413;
	case RequestUriTooLong = 414;
	case UnsupportedMediaType = 415;
	case RequestedRangeNotSatisfiable = 416;
	case ExpectationFailed = 417;
	case IAmATeapot = 418;                                                   // RFC2324
	case MisdirectedRequest = 421;                                           // RFC7540
	case UnprocessableEntity = 422;                                          // RFC4918
	case Locked = 423;                                                       // RFC4918
	case FailedDependency = 424;                                             // RFC4918
	case TooEarly = 425;                                                     // RFC-ietf-httpbis-replay-04
	case UpgradeRequired = 426;                                              // RFC2817
	case PreconditionRequired = 428;                                         // RFC6585
	case TooManyRequests = 429;                                              // RFC6585
	case RequestHeaderFieldsTooLarge = 431;                                  // RFC6585
	case UnavailableForLegalReasons = 451;                                   // RFC7725
	case InternalServerError = 500;
	case NotImplemented = 501;
	case BadGateway = 502;
	case ServiceUnavailable = 503;
	case GatewayTimeout = 504;
	case VersionNotSupported = 505;
	case VariantAlsoNegotiatesExperimental = 506;                            // RFC2295
	case InsufficientStorage = 507;                                          // RFC4918
	case LoopDetected = 508;                                                 // RFC5842
	case NotExtended = 510;                                                  // RFC2774
	case NetworkAuthenticationRequired = 511;                                // RFC6585

}